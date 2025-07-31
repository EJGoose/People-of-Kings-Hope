import './bootstrap';
//wait for Alpine to fully initialise
document.addEventListener('alpine:init', () => {
    //take initial data from the view and use it to populate form
    Alpine.data('form_handler', (initialContactData, initalPagination) => ({
        //set reactive variables
        loadingContent: false,
        contactData: initialContactData,
        pageData: initalPagination,
        searchQuery: null,
        currentPage: initalPagination.page,

        //get values for calculated or combined variables
        get pageEnd(){
            return Math.ceil(this.pageData.num_results/this.pageData.per_page);
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

        //submit a request to the API route manager
        async submit(){
            try{
                this.loadingContent= true;
                //send a post request with query and page data
                const response = await fetch('/api/contact/search',{
                   method:'POST',
                   headers:{
                        'Content-Type':'application/json',
                    },
                    body: JSON.stringify({q:this.searchQuery, p:this.currentPage})
                });

                //repopulate reactive variables
                const newData = await response.json();
                this.contactData = newData.apiResponse.data;
                this.pageData = newData.apiResponse.pagination;
                this.currentPage = this.pageData.page > this.pageEnd ? this.pageEnd : this.pageData.page; //reset page counter to match the new data

            } catch (error) {
                //handle search query errors
                this.contactData = {"error": `Oh dear, that search was invalid, please try again. Error Details: ${error}`};
                console.error('Search failure:', error);
            } finally {
                this.loadingContent = false;
            }
        },
    }))
})

