import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.data('form_handler', (initialContactData, initalPagination) => ({
        loadingContent: false,
        contactData: initialContactData,
        pageData: initalPagination,
        searchQuery: null,
        currentPage: initalPagination.page,

        get pageEnd(){
            return Math.ceil(this.pageData.num_results/this.pageData.per_page);
        },

        get range(){
            return `${this.pageStart}-${this.pageEnd}`;
        },

        get errorMsg(){
            return this.contactData['error'] != null ? this.contactData['error'] : ""
        },

        get activeData(){
            return {
                'q':this.searchQuery,
                'p':this.currentPage,
                'results_count':this.pageData.num_results,
                'pageEnd':this.pageEnd,
                'more':this.pageData.next_page
            };
        },

        async submit(){
            try{
                this.loadingContent= true;
                const response = await fetch('/api/contact/search',{
                   method:'POST',
                   headers:{
                        'Content-Type':'application/json',
                    },
                    body: JSON.stringify({q:this.searchQuery, p:this.currentPage})
                });

                const newData = await response.json();
                this.contactData = newData.apiResponse.data;
                this.pageData = newData.apiResponse.pagination;
                this.currentPage = this.pageData.page > this.pageEnd ? this.pageEnd : this.pageData.page;

            } catch (error) {
                console.error('Search failure:', error);
            } finally {
                this.loadingContent = false;
            }
        },
    }))
})

