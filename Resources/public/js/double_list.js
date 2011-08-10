var genemuDoubleList = {
    init: function(id, className) {
        form = genemuDoubleList.get_current_form(id);
        
        callback = function() {
            genemuDoubleList.submit(form, className);
        }
        
        if(form.addEventListener) {
            form.addEventListener('submit', callback, false);
        } else if(form.attachEvent) {
            form.attchEvent('onsubmit', callback);
        }
    },
    move: function(srcId, destId) {
        var src = document.getElementById(srcId);
        var dest = document.getElementById(destId);
        
        for(var i = 0; i < src.options.length; i++) {
            if(src.options[i].selected) {
                dest.options[dest.length] = new Option(src.options[i].text, src.options[i].value);
                src.options[i] = null;
                --i;
            }
        }
    },
    submit: function(form, className) {
        var element;
        
        for(var i = 0; i < form.elements.length; i++) {
            element = form.elements[i];
            
            if(element.type == 'select-multiple') {
                if(element.className == className+'-selected') {
                    for(var j = 0; j < element.options.length; j++) {
                        element.options[j].selected = true;
                    }
                }
            }
        }
    },
    get_current_form: function(el) {
        if('form' != el.tagName.toLowerCase()) {
            return genemuDoubleList.get_current_form(el.parentNode);
        }

        return el;
    }
};