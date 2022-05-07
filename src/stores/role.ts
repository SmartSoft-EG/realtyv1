import axios from 'axios'
import { defineStore } from 'pinia'

export const useRoleStore = defineStore(
    {
        id: 'role',
        state: () => ({
            roles: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetchRoles() {
                axios.get('role').then((res) => {
                    this.roles = res.data
                })
            },

            saveRole(role: any) {
                //update
                if (role.id !== 0) {
                    axios.put(`role/${role.id}`, role).then((res) => {
                        this.roles = this.roles.filter((u: any) => u.id !== role.id) //remove old row
                        this.roles.unshift(res.data) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('role', role).then((res) => {
                        this.roles.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
        },

    })
