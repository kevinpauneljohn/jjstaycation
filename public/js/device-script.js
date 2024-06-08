WTN.enablePullToRefresh(true);

//Pass values as true or false
//To enable pass true and to disable pass the value as false. By default Pull to refresh is enabled on all pages.

WTN.statusBar({
    style:"dark",
    color:"1a81e1",
    overlay:true //Only for android
});


const { contacts } = window.WTN

contacts.getPermissionStatus({
    callback: function(data){
        //data.status contains permission status
    }
})


contacts.getAll({
    callback: function(data){
        //data.contacts contains all contact
        alert(data);
    }
})
