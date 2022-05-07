import { defineStore } from 'pinia'
import axios from 'axios'
export const useCommitteesStore = defineStore('committee', {
  state: () => {
    return {
      committees: [],
      loading: false,
    }
  },

  getters: {
    firstStageCommittes: state => state.committees.filter(s => s.type == '1st_stage'),
    secondStageCommittees: state => state.committees.filter(s => s.type == '2nd_stage'),
    committeesByStage: state => (stage: string) => state.committees.filter(s => s.type === stage),
  },

  actions: {

    fetchCommittees() {
      this.loading = true
      axios.get('conf-committee?all=true').then((res) => {
        this.committees = res.data
        this.loading = false
      }).finally(() => this.loading = false)
    },

  },

})
