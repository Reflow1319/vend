import {pluralize, camelize} from 'inflection'


function getStates(store) {
    const states = {}

    states[store] = {}
    states[pluralize(store)] = []

    return states
}

function getGetters(store) {
    const getters = {}

    getters[store] = (state) => state[store]
    getters[pluralize(store)] = (state) => state[pluralize(store)]

    return getters
}

function getMutations(store) {
    const mutations = {}

    mutations[camelize('set_' + store, true)] = (state, r) => {
        state[store] = r
    }

    mutations[camelize('set_' + pluralize(store), true)] = (state, r) => {
        state[pluralize(store)] = r
    }

    mutations[camelize('create_' + store, true)] = (state, r) => {
        state[store] = r
        state[pluralize(store)].push(r)
    }

    mutations[camelize('update_' + store, true)] = (state, r) => {
        state[store] = r
        state[pluralize(store)] = state[pluralize(store)].map(e => {
            if (e.id === r.id) return r
            return e
        })
    }

    mutations[camelize('delete_' + store, true)] = (state, r) => {
        state[store] = {}
        state[pluralize(store)] = state[pluralize(store)].filter(e => r.id !== e.id)
        console.log(state[pluralize(store)])
    }

    return mutations
}

function getActions(store, baseUrl) {

    const actions = {};

    // Index
    const get = camelize('get_' + pluralize(store), true)
    actions[get] = ({commit}, r) => {
        return axios.get(r && r._url || baseUrl).then(res => {
            commit(camelize('set_' + pluralize(store), true), res.data)
            return res.data
        })
    }

    // GetOne
    const getOne = camelize('get_' + store, true)
    actions[getOne] = ({commit}, r) => {
        return axios.get(r && r._url || baseUrl + '/' + r.id).then(res => {
            commit(camelize('set_' + store, true), res.data)
            return res.data
        })
    }

    // Update
    const update = camelize('update_' + store, true)
    actions[update] = ({commit}, r) => {
        axios.get(r && r._url || baseUrl + '/' + r.id).then(res => {
            commit(camelize('update_' + pluralize(store), true), res.data)
        })
    }

    // Save
    const save = camelize('save_' + store, true)
    actions[save] = ({commit}, r) => {
        if (r.id) {
            return axios.put(r._url || baseUrl + '/' + r.id, r).then(res => {
                commit(camelize('update_' + store, true), res.data)
                return res.data
            })
        } else {
            return axios.post(r._url || baseUrl, r).then(res => {
                commit(camelize('create_' + store, true), res.data)
                return res.data
            })
        }
    }

    // Destroy
    const destroy = camelize('delete_' + store, true)
    actions[destroy] = ({commit}, r) => {
        axios.delete(r._url || baseUrl + '/' + r.id).then(() => {
            commit(camelize('delete_' + store, true), r)
        })
    }

    return actions
}

export function makeResource(store, baseUrl, additional) {

    if (!baseUrl) baseUrl = pluralize(store)

    const resource = {
        state: getStates(store),
        getters: getGetters(store),
        mutations: getMutations(store),
        actions: getActions(store, baseUrl)
    }

    return mergeInto(resource, additional)
}

export function mergeInto(resource, additional) {
    const types = ['state', 'getters', 'mutations', 'actions']

    if (!additional) return resource;

    for (let i in types) {
        resource[types[i]] = additional[types[i]]
            ? Object.assign(additional[types[i]], resource[types[i]])
            : resource[types[i]]
    }

    return resource
}