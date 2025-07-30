import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.data('form_handler', (initialContactData, initalPagination) => ({
        loadingContent: false,
        contactData: initialContactData,
        pageData: initalPagination,
        searchQuery: null,
        currentPage: initalPagination.page,

        get pageStart(){
             return this.pageData.page > 1 ? this.pageData.page * this.pageData.per_page : this.pageData.page;
        },

        get pageEnd(){
            return (this.pageStart + this.pageData.per_page) < this.pageData.num_results ? this.pageStart + this.pageData.per_page : this.pageData.num_results;
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
                'range':this.range,
                'more':this.pageData.next_page
            };
        },

        async submit(){
            try{
                console.log("trying search")
                console.log("current data: ", this.contactData)
                console.log("Query and Page: ", this.searchQuery, this.currentPage)
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
                console.log("new data: ", this.contactData)
                this.pageData = newData.apiResponse.pagination;
                console.log("new page: ", this.pageData)

            } catch (error) {
                console.error('Search failure:', error);
            } finally {
                this.loadingContent = false;
            }
        },
    }))
})

