


$(document).ready(function(){


    function GenerateInputField (name, $) {

        this.name = name,
        this.button = $('button[name="add-new-title"]').get(0),

        this.add  = function() {

            this.button.addEventListener('click', (event) => {

                div = $(event.target).closest(".card-body").find("div").last();
                copy = div.clone();
                $(div).append(copy);
            })
        }

        this.remove  = function() {
            return 'remove'
        }
    }

    const str = new GenerateInputField('add-new-title', $);
    str.add();

});






