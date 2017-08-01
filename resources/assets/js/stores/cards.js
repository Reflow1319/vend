import {makeResource} from './resource'
import moment from 'moment'

export default makeResource('card', null, {
    state: {
        filteredCards: [],
        filters: {}
    },
    getters: {
        filters: state => state.filters,
        filteredCards: state => state.filteredCards
    },
    mutations: {
        setCardsFilter(state, filter) {
            state.filters = filter

            if (!state.filters) {
                state.filteredCards = state.cards;
            }
            let filterUser, filterDate, filterTitle
            state.filteredCards = state.cards.filter(c => {
                filterUser = filterDate = filterTitle = true
                if (state.filters.user) {
                    filterUser = c.assigned_to === state.filters.user.id
                }
                if (state.filters.title) {
                    filterTitle = c.title.toLowerCase().indexOf(state.filters.title.toLowerCase()) !== -1
                }
                if (state.filters.dueDate && state.filters.dueDate.length > 0) {
                    let dueDate = moment(c.due_date, 'YYYY-MM-DD')
                    filterDate = dueDate > state.filters.dueDate[0]
                        && dueDate <= state.filters.dueDate[1]
                }
                return (filterUser && filterDate && filterTitle)
            })
        }
    }
})