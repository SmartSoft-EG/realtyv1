import { defineStore } from 'pinia'

export const useNotifyStore = defineStore({
  id: 'notify',
  state: () => ({
    text: '',
    show: false,
    color: '',
    loading: false,
    disable_loading: false,

  }),

  actions: {
    startLoading() {
      if (!this.disable_loading)
        this.loading = true
    },

    stopLoading() {
      this.loading = false
      this.disable_loading = false
    },

    showMessage(text: String, color: String) {
      this.text = text
      this.color = color
      this.show = true
      this.stopLoading()
    },

    showUpdate(text = 'item was updated') {
      this.text = text
      this.color = 'primary'
      this.show = true
      this.stopLoading()
    },

    showDelete(text = 'item was deleted') {
      this.text = text
      this.color = 'error'
      this.show = true
      this.stopLoading()
    },

    showSave(text = 'item was saved') {
      this.text = text
      this.color = 'success'
      this.show = true
      this.stopLoading()
    },

    showSuccessResponse(res) {
      this.text = res
      this.color = 'success'
      this.show = true
      this.stopLoading()
    },
  },

})
