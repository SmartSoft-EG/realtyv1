import axios from 'axios'
import { defineStore } from 'pinia'
export const useAccountStore = defineStore(
    {
        id: 'account',
        state: () => ({
            accounts: [],
            show_add_dialog: false,
        }),

        getters: {

            mapped_accounts: state => state.accounts.map(a => {
                return { id: a.id, parent_id: a.parent_id, label: a.name, balance: a.balance }
            }),
            accounts_tree: state => {
                let parent_id = 1;

                function parseTree(nodes: any, parentID: any) {
                    let tree = [];
                    //	let length = nodes.length;
                    nodes.forEach((n) => {
                        if (n.parent_id == parentID) {
                            n.children = parseTree(nodes, n.id);
                            tree.push(JSON.parse(JSON.stringify(n)));
                        }
                    });
                    return tree;
                }

                return parseTree(state.accounts, parent_id);



            }
        },

        actions: {
            fetchAccounts() {
                axios.get('account').then((res) => {
                    this.accounts = res.data
                })
            },

            saveAccount(account: any) {
                //update
                if (account.id !== 0) {
                    axios.put(`account/${account.id}`, account).then((res) => {
                        this.accounts = this.accounts.filter((u: any) => u.id !== account.id) //remove old row
                        this.accounts.unshift(account) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('account', account).then((res) => {
                        this.accounts.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
            deleteAccount(id: number) {

                axios.delete('account/' + id).then(() => {
                    this.accounts = this.accounts.filter(s => s['id'] !== id)
                })
            },
        },



    })
