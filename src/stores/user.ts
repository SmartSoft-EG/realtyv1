import axios from 'axios'
import { defineStore } from 'pinia'

export const useUserStore = defineStore(
  {
    id: 'user',
    state: () => ({
      users: [],
      show_add_dialog: false,
    }),

    getters: {

    },

    actions: {
      fetchUsers() {
        axios.get('user').then((res) => {
          this.users = res.data
        })
      },

      saveUser(user: any) {
        //update
        if (user.id !== 0) {
          axios.put(`user/${user.id}`, user).then((res) => {
            this.users = this.users.filter((u: any) => u.id !== user.id) //remove old row
            this.users.unshift(res.data) //prepend add new row
            this.show_add_dialog = false
          })
        }

        //create
        else {
          axios.post('user', user).then((res) => {
            this.users.push(res.data)
            this.show_add_dialog = false
          })
        }

      },
    },

  })
