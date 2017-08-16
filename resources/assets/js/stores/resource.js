import {pluralize, camelize} from 'inflection'
import stringTemplate from 'string-template'
import http from '../http'

function Resource ({store, baseUrl, additional, options}, instance) {
  if (!(this instanceof Resource)) {
    return console.error('Resource is a constructor, you should call with new')
  }

  this.store = store
  this.baseUrl = baseUrl
  this.additional = additional || {}
  this.options = options || {}

  if (!this.baseUrl) this.baseUrl = pluralize(this.store)

  const resource = {
    state: this.getStates(),
    getters: this.getGetters(),
    mutations: this.getMutations(),
    actions: this.getActions()
  }

  return this.mergeInto(resource)
}

Resource.prototype.getStates = function () {
  const states = {}

  states[camelize(this.store, true)] = {}
  states[camelize(pluralize(this.store), true)] = []

  return states
}

Resource.prototype.getGetters = function () {
  const getters = {}

  getters[camelize(this.store, true)] = (state) => state[camelize(this.store, true)]
  getters[camelize(pluralize(this.store), true)] = (state) => state[camelize(pluralize(this.store), true)]

  return getters
}

Resource.prototype.getMutations = function () {
  const mutations = {}

  mutations[camelize('set_' + this.store, true)] = (state, r) => {
    state[this.store] = r
  }

  mutations[camelize('set_' + pluralize(this.store), true)] = (state, r) => {
    state[camelize(pluralize(this.store), true)] = r
  }

  mutations[camelize('create_' + this.store, true)] = (state, r) => {
    state[this.store] = r
    if (this.options.addType === 'prepend') {
      state[camelize(pluralize(this.store), true)].unshift(r)
    } else {
      state[camelize(pluralize(this.store), true)].push(r)
    }
  }

  mutations[camelize('update_' + this.store, true)] = (state, r) => {
    state[this.store] = r
    state[camelize(pluralize(this.store), true)] = state[camelize(pluralize(this.store), true)].map(e => {
      if (e.id === r.id) return r
      return e
    })
  }

  mutations[camelize('delete_' + this.store, true)] = (state, r) => {
    state[this.store] = {}
    state[camelize(pluralize(this.store), true)] = state[camelize(pluralize(this.store), true)].filter(e => r.id !== e.id)
  }

  return mutations
}

Resource.prototype.getActions = function () {
  const actions = {}

  // Index
  const get = camelize('get_' + pluralize(this.store), true)
  actions[get] = ({commit}, r) => {
    return http.get(this.getUrl(r)).then(res => {
      const data = this.options.paginated
        ? res.data.data
        : res.data

      commit(camelize('set_' + pluralize(this.store), true), data)
      return res.data
    })
  }

  // GetOne
  const getOne = camelize('get_' + this.store, true)
  actions[getOne] = ({commit}, r) => {
    return http.get(this.getUrl(r) + '/' + r.id).then(res => {
      commit(camelize('set_' + this.store, true), res.data)
      return res.data
    })
  }

  // Update
  const update = camelize('update_' + this.store, true)
  actions[update] = ({commit}, r) => {
    http.get(this.getUrl(r) + '/' + r.id).then(res => {
      commit(camelize('update_' + pluralize(this.store), true), res.data)
    })
  }

  // Save
  const save = camelize('save_' + this.store, true)
  actions[save] = ({commit}, r) => {
    if (r.id) {
      return http.put(this.getUrl(r) + '/' + r.id, r).then(res => {
        commit(camelize('update_' + this.store, true), res.data)
        return res.data
      })
    } else {
      return http.post(this.getUrl(r), r).then(res => {
        commit(camelize('create_' + this.store, true), res.data)
        return res.data
      })
    }
  }

  // Destroy
  const destroy = camelize('delete_' + this.store, true)
  actions[destroy] = ({commit}, r) => {
    http.delete(this.getUrl(r) + '/' + r.id).then(() => {
      commit(camelize('delete_' + this.store, true), r)
    })
  }

  return actions
}

Resource.prototype.getUrl = function (r) {
  return r && r.urlParams
    ? stringTemplate((r._url ? r._url : this.baseUrl), r.urlParams)
    : r && r._url ? r._url : this.baseUrl
}

Resource.prototype.mergeInto = function (resource) {
  const types = ['state', 'getters', 'mutations', 'actions']

  if (!this.additional) return resource

  for (let i in types) {
    resource[types[i]] = this.additional[types[i]]
      ? Object.assign(this.additional[types[i]], resource[types[i]])
      : resource[types[i]]
  }

  return resource
}

export default Resource
