// au click sur le button
$('#add-image').click(function () {
    // Je récupère le nombre d'entrée que je vais créer
    const index = +$('#widgets-counter').val();

    // Je récupère le prototype des entrées
    const tmpl = $('#post_images').data('prototype').replace(/__name__/g, index);

    //J'injecte se code au sein de la div
    $('#post_images').append(tmpl)

    // On incrémente la valeur du compteur de widgets
    $('#widgets-counter').val(index + 1);
    handleDeleteButtons();

})
// Gestion des boutons de suppression
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        // On récupère le cible contenu dans l'attribut data-target
        const target = this.dataset.target;

        $(target).remove();

        console.log(target)

    })
}

function updateCounter(){
    const count = +$("#post_images div.form-group").length;

    $('#widgets-counter').val(count);

}

updateCounter();
handleDeleteButtons();