function is_array(o) { 
    if(o != null && typeof o == 'object') { 
            return (typeof o.push == 'undefined') ? false : true; 
    } else { 
            return false; 
    } 
} 

function is_empty(o) {
    return (o.length < 1);
}

if(!Array.indexOf){
    Array.prototype.indexOf = function(obj) {
        for(var i=0; i<this.length; i++) {
            if(this[i]==obj) {
                return i;
            }
        }
        return -1; //Item not found
    }
}