import { defineStore } from 'pinia'
import nestedGroupBy from 'nested-groupby'
import axios from 'axios'

export const usePresentationStore = defineStore('presentation', {
  state: () => {
    return {
      presentations: [],
      loading: false,
    }
  },

  getters: {
    grouped_members: state => nestedGroupBy(state.presentations, ['department_id']),
    // firstStagepresentations: (state) => state.presentations.filter(s => s.type == '1st_stage'),
    // secondStagepresentations: (state) => state.presentations.filter(s => s.type == '2nd_stage'),
    // sumFirstStagepresentations: (state) => state.firstStagepresentations.reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    // sumSecondStagepresentations: (state) => state.secondStagepresentations.reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    // sumpresentationsByType: (state) => (type) => state.presentations.filter(s => s.type == type).reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    // stagepresentationsByType: (state) => (type) => state.presentations.filter(s => s.type == type),

  },

  actions: {
    fetchPresentations() {
      this.loading = true
      axios.get('conf-member').then((res) => {
        this.presentations = res.data
        this.loading = false
      }).finally(() => this.loading = false)
    },

    deletePresentation(id) {
      axios.delete(`conf-member/${id}`).then(() => {
        this.presentations = this.presentations.filter(s => s.id !== id)
      })
    },
  },

})
