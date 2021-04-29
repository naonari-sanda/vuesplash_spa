const state = {
  code: null
}

const mutations = {
  setCode(State, code) {
    state.code = code
  }
}

export default {
  namespased: true,
  state,
  mutations
}
