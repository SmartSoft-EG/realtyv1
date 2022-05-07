import { defineStore } from 'pinia'
import axios from 'axios'

export const useScoreStore = defineStore('score', {
  state: () => {
    return {
      scores: [],
      loading: false,
    }
  },

  getters: {
    firstStageScores: state => state.scores.filter(s => s.type == '1st_stage'),
    secondStageScores: state => state.scores.filter(s => s.type == '2nd_stage'),
    sumFirstStageScores: state => state.firstStageScores.reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    sumSecondStageScores: state => state.secondStageScores.reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    sumScoresByType: state => type => state.scores.filter(s => s.type == type).reduce((a, b) => a + parseFloat(b.excellent_mark), 0),
    stageScoresByType: state => type => state.scores.filter(s => s.type == type),

  },

  actions: {
    fetchScores() {
      this.loading = true
      axios.get('conf-score').then((res) => {
        this.scores = res.data
        this.loading = false
      }).finally(() => this.loading = false)
    },
  },

})
