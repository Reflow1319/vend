import {pluralize, camelize} from 'inflection'
import stringTemplate from 'string-template'

function getStates (store) {
    const states = {}

    states[camelize(store, true)] = {}
    states[camelize(pluralize(store), true)] = []

    return states
}

function getGetters (store) {
    const getters = {}

    getters[camelize(store, true)] = (state) => state[camelize(store, true)]
    getters[camelize(pluralize(store), true)] = (state) => state[camelize(pluralize(store), true)]

    return getters
}

function getMutations (store) {
    const mutations = {}

    mutations[camelize('set_' + store, true)] = (state, r) => {
        state[store] = r
    }

    mutations[camelize('set_' + pluralize(store), true)] = (state, r) => {
        state[camelize(pluralize(store), true)] = r
    }

    mutations[camelize('create_' + store, true)] = (state, r) => {
        state[store] = r
        state[camelize(pluralize(store), true)].push(r)
    }

    mutations[camelize('update_' + store, true)] = (state, r) => {
        state[store] = r
        state[camelize(pluralize(store), true)] = state[camelize(pluralize(store), true)].map(e => {
            if (e.id === r.id) return r
            return e
        })
    }

    mutations[camelize('delete_' + store, true)] = (state, r) => {
        state[store] = {}
        state[camelize(pluralize(store), true)] = state[camelize(pluralize(store), true)].filter(e => r.id !== e.id)
    }

    return mutations
}

function getActions (store, baseUrl) {

    const actions = {};

    // Index
    const get = camelize('get_' + pluralize(store), true)
    actions[get] = ({commit}, r) => {
        return axios.get(getUrl(baseUrl, r)).then(res => {
            commit(camelize('set_' + pluralize(store), true), res.data)
            return res.data
        })
    }

    // GetOne
    const getOne = camelize('get_' + store, true)
    actions[getOne] = ({commit}, r) => {
        return axios.get(getUrl(baseUrl, r) + '/' + r.id).then(res => {
            commit(camelize('set_' + store, true), res.data)
            return res.data
        })
    }

    // Update
    const update = camelize('update_' + store, true)
    actions[update] = ({commit}, r) => {
        axios.get(getUrl(baseUrl, r) + '/' + r.id).then(res => {
            commit(camelize('update_' + pluralize(store), true), res.data)
        })
    }

    // Save
    const save = camelize('save_' + store, true)
    actions[save] = ({commit}, r) => {
        if (r.id) {
            return axios.put(getUrl(baseUrl, r) + '/' + r.id, r).then(res => {
                commit(camelize('update_' + store, true), res.data)
                return res.data
            })
        } else {
            return axios.post(stringTemplate(baseUrl, r.urlParams), r).then(res => {
                commit(camelize('create_' + store, true), res.data)
                return res.data
            })
        }
    }

    // Destroy
    const destroy = camelize('delete_' + store, true)
    actions[destroy] = ({commit}, r) => {
        axios.delete(getUrl(baseUrl, r) + '/' + r.id).then(() => {
            commit(camelize('delete_' + store, true), r)
        })
    }

    return actions
}

function getUrl (baseUrl, r) {
    return r && r.urlParams
        ? stringTemplate(baseUrl, r.urlParams)
        : baseUrl
}

export function makeResource (store, baseUrl, additional) {

    if (!baseUrl) baseUrl = pluralize(store)

    const resource = {
        state: getStates(store),
        getters: getGetters(store),
        mutations: getMutations(store),
        actions: getActions(store, baseUrl)
    }

    return mergeInto(resource, additional)
}

export function mergeInto (resource, additional) {
    const types = ['state', 'getters', 'mutations', 'actions']

    if (!additional) return resource;

    for (let i in types) {
        resource[types[i]] = additional[types[i]]
            ? Object.assign(additional[types[i]], resource[types[i]])
            : resource[types[i]]
    }

    return resource
}