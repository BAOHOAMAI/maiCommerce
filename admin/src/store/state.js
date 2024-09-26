export default {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {}
    },
    products: {
        loading : false,
        data: [],
        from:null ,
        to:null ,
        links : [],
        limit : null,
        total : null
    },
}