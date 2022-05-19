import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useOwnerStore = defineStore(
    {
        id: 'owner',
        state: () => ({
            owners: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetch() {
                axios.get('owner').then((res) => {
                    this.owners = res.data
                })
            },

            save(owner: any) {
                //update
                if (owner.id !== 0) {
                    axios.put(`owner/${owner.id}`, owner).then((res) => {
                        this.owners = this.owners.filter((u: any) => u.id !== owner.id) //remove old row
                        this.owners.unshift(owner) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('owner', owner).then((res) => {
                        this.owners.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
            delete(id: number) {

                axios.delete('owner/' + id).then(() => {
                    this.owners = this.owners.filter(s => s['id'] !== id)
                })
            },
        },



    })
