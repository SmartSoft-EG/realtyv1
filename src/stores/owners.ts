import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useOwnerStore = defineStore(
    {
        id: 'owner',
        state: () => ({
            list: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetch() {
                axios.get('owner').then((res) => {
                    this.list = res.data
                })
            },

            save(owner: any) {
                //update
                if (owner.id !== 0) {
                    axios.put(`owner/${owner.id}`, owner).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== owner.id) //remove old row
                        this.list.unshift(owner) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('owner', owner).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
            delete(id: number) {

                axios.delete('owner/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
