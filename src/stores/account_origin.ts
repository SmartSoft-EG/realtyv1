import axios from 'axios'
import { defineStore } from 'pinia'
export const useAccountOriginStore = defineStore(
    {
        id: 'account_origin',
        state: () => ({
            list: [],
            show_add_dialog: false,

        }),

        getters: {
            tree: state => {
                function makTree(nodes: any, parentId: number) {
                    return nodes
                        .filter((node) => node.parent_id === parentId)
                        .reduce(
                            (tree, node) => [
                                ...tree,
                                {
                                    ...node,
                                    children: makTree(nodes, node.id),
                                },
                            ],
                            [],
                        )
                }

                return makTree(state.list, 1)
            }
        },

        actions: {
            fetch() {
                axios.get('account-origin').then((res) => {
                    this.list = res.data
                })
            },

            save(account: any) {
                //update
                if (account.id !== 0) {
                    axios.put(`account-origin/${account.id}`, account).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== account.id) //remove old row
                        this.list.unshift(account) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('account-origin', account).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },

            delete(id: number) {

                axios.delete('account-origin/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
