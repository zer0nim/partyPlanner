// javascript pour le plannings


// mini doc de ce qu'il y a d'important à savoir
/*
fonction pour supprimer un évenement par son id
$('#calendar').fullCalendar( 'removeEvents' ,idevent );

fonction qui met à jour le planning
$('#calendar').fullCalendar('updateEvents', event);

fonction qui ajoute un evenement
$('#calendar').fullCalendar( 'addEventSource', source ); source = comme dans view

fonction qui retourne un event source par l'id
$('#calendar').fullCalendar( 'getEventSourceById', id );

callback pour si l'utilisateur click sur un événement
eventClick: function(event, element) {
},

// fonction quand un évenemnt est changé de place
eventDrop: function(event, delta, revertFunc) {
},

objet event :
    id
    title
    start
    end
    color
    backgroundColor
    textColor
*/

// fonction pour afficher le planning modifiable
function afficheModifieEvenement(evenement){
	// evenement == null : ajout d'un nouvel evenement

	var id = -1;
	var description = "";
	var jourdebut = "";
	var heuredebut = "";
	var jourfin = "";
	var heurefin = "";

	if (evenement != null){
		id = evenement.id;
		description = evenement.title;

		jourdebut = evenement.start.format('DD/MM/YYYY');
		heuredebut = evenement.start.format('HH:mm');

		if (evenement.end != null){
			jourfin = evenement.end.format('DD/MM/YYYY');
			heurefin = evenement.end.format('HH:mm');
		}

	}

	// créér une popup de modification
	var popup = 'Evènement'
		+'<div class="champ input-group">'
			+'<span class="libelle input-group-addon">Description</span>'
			+'<textarea class="form-control" id="description" value="'+description+'">'+description+'</textarea>'
		+'</div>'
		+'<div class="champ col-sm-6">'
			+'<div class="input-group">'
				+'<span class="libelle input-group-addon">Jour de début</span>'
				+'<input class="form-control" id="jourdebut" name="date" placeholder="JJ/MM/AAAA" type="text" value="'+jourdebut+'" required/>' //+'<input type="date" id="jourdebut" class="form-control" value="'+jourdebut+'" required>'
			+'</div>'
			+'<div class="input-group">'
				+'<span class="libelle input-group-addon">Heure de début</span>'
				+'<input type="time" id="heuredebut" class="form-control" value="'+heuredebut+'">'
			+'</div>'
		+'</div>'
		+'<div class="champ col-sm-6">'
			+'<div class="input-group">'
				+'<span class="libelle input-group-addon">Jour de fin</span>'
				+'<input class="form-control" id="jourfin" name="date" placeholder="JJ/MM/AAAA" type="text" value="'+jourfin+'"/>' //+'<input type="date" id="jourfin" class="form-control" value="'+jourfin+'">'
			+'</div>'
			+'<div class="input-group">'
				+'<span class="libelle input-group-addon">Heure de fin</span>'
				+'<input type="time" id="heurefin" class="form-control" value="'+heurefin+'">'
			+'</div>'
		+'</div>'
		+'<div>';

	// si c'est un évenement deja crée, on ajoute un bouton supprimer
	if (id >= 0){
		popup += '<button class="btn-popup btn-md btn-primary" onClick="delEvenement('+id+')">Supprimer</button>';
	}

	popup += '<button class="btn-popup btn-md btn-primary" onClick="cacher()">Annuler</button>'
			+'<button class="btn-popup btn-md btn-primary" onClick="modifEvenement(null, '+id+')">Enregistrer</button>'
		+'</div>';

	$('#popup').html(popup);
	resizepopup();

	// Effet de transition
	$('#fond-popup').fadeTo("",0.6);
	$('#popup').fadeIn(400);

	// initialisation des datepicker
	var date_input1 = $('#jourdebut');
	var date_input2 = $('#jourfin');
	var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

	var options1 = {
		language: "fr-FR",
		format: 'dd/mm/yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true
	};

	var options2 = {
		language: "fr-FR",
		startDate: document.getElementById('jourdebut').value,
		format: 'dd/mm/yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true
	};

	date_input2.datepicker(options2);
	date_input1.datepicker(options1).on("changeDate", function(e) {
        date_input2.datepicker('setStartDate', e.date);
    });;

}

// fonction pour cacher la fenetre de selection si la fenetre est modifié
function hideDatePicker(){
	$('#jourdebut').datepicker('hide');
	$('#jourdebut').blur();
	$('#jourfin').datepicker('hide');
	$('#jourfin').blur();
}

$(window).resize(function () {
    resizepopup();
	hideDatePicker();
});

$(window).scroll(function () {
	hideDatePicker();
});

// pour changer la taille du popup et du fond en fontion de la taille de la fenetre
function resizepopup(){
	// On récupère la largeur de la fenetre et la hauteur de la page
	var winH = $(document).height();
	var winW = $(window).width();

	// le fond aura la taille du document
	fondpopup = $('#fond-popup');
	fondpopup.css({'width':winW,'height':winH});
	fondpopup.css('top', winH/2 - fondpopup.height()/2);
	fondpopup.css('left', winW/2 - fondpopup.width()/2);

	// On récupère la largeur de l'écran
	var winH = $(window).height();

	// On met la fenêtre modale au centre de l'écran
	popup = $('#popup');
	popup.css('top', winH/2 - popup.height()/2);
	popup.css('left', winW/2 - popup.width()/2);

}

// fonction pour caché la popup
function cacher(){
	$('#popup').fadeOut(300);
	$('#fond-popup').fadeTo("",0,function(){
		$('#fond-popup, #popup').hide();
		$('#popup').html('');
	});

}

// fonction pour supprimer un evenement de la bd
function delEvenement(id){

	swal({
		title: "Supression",
		text: "Etes-vous sûr de vouloir supprimer cet évènement ?\nToutes les données seront perdues !",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Supprimer",
		cancelButtonText: "Annuler"
		},  function(){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if(xhttp.readyState === XMLHttpRequest.DONE){
				if (xhttp.status === 200){
					$('#calendar').fullCalendar( 'removeEvents' ,id);
					cacher();
				}else{
					serverLost();
				}
				}
			};

			xhttp.open("POST", "planning-modifie.php", true);
			xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhttp.send("action=delevenement&id="+id);
		});

}

// fonction pour enregister les modifications d'un evenement dans la bd
function modifEvenement(evenement, idchamp){ // eventobject
	// idchamp sert seulement si le planning est modifié par les champs
	// evenement == null : planning modifier via les champs de modifications
	// evenement != null : planning modifié via le js du planning
	// id < 0 : nouvelle evenement
	// id >+ 0 / evenement modifié

	var id;
	var description;
	var start;
	var end;

	if (evenement == null){
		// recuperer les champs de description
		id = idchamp;
		description = document.getElementById('description').value;

		// si le jour de début n'est pas définit, message d'erreur
		if (document.getElementById('jourdebut').value == ""){
			swal({
				title: "Erreur",
				text: "Vous devez donner le date de début de l'évènement !",
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Ok"
				},  function(){
				// ne rien faire
			});
			return null;
		}

		// creation des dates au format 'Y-m-d H:i:s'
		var tabdate = document.getElementById('jourdebut').value.split("/");
		start = tabdate[2] +"-"+ tabdate[1] +"-"+ tabdate[0];
		if (document.getElementById('heuredebut').value != ""){
			start += " "+document.getElementById('heuredebut').value+":00"
		}else{
			start += " 00:00:00";
		}

		if (document.getElementById('jourfin').value != ""){
			tabdate = document.getElementById('jourfin').value.split("/");
			end = tabdate[2] +"-"+ tabdate[1] +"-"+ tabdate[0];
		}else{
			end = tabdate[2] +"-"+ tabdate[1] +"-"+ tabdate[0];
		}
		if (document.getElementById('heurefin').value != ""){
			end += " "+document.getElementById('heurefin').value+":00";
		}else{
			if (document.getElementById('heuredebut').value != ""){
				end += " "+document.getElementById('heuredebut').value+":00"
			}else{
				end += " 00:00:00";
			}
		}

	}else{
		id = evenement.id;
		description = evenement.title;
		start = evenement.start.format().replace("T", " ");
		if (evenement.end != null){
			end = evenement.end.format().replace("T", " ");
		}else{
			end = start;
		}
	}

	// modification de la vue apres réponse du serveur
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
	    if(xhttp.readyState === XMLHttpRequest.DONE){
			if (xhttp.status === 200){

				if (evenement == null){
					if (id >= 0){
						$('#calendar').fullCalendar( 'removeEvents' ,id);
					}else{
						id = this.responseText;
					}

					$('#calendar').fullCalendar( 'addEventSource', {
						events: [
							{
							id: id,
							title: description,
							start: start,
							end: end
							}
						]}
					);
				}

				cacher();
			}else{
				serverLost();
			}
	    }
	};

	xhttp.open("POST", "planning-modifie.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	if (id < 0){
		xhttp.send("action=addevenement&id="+id+"&description="+description+"&start="+start+"&end="+end);
	}else{
		xhttp.send("action=updateevenement&id="+id+"&description="+description+"&start="+start+"&end="+end);
	}

}

// function pour si le client pert la connexion avec le serveur web
// (les requettes ne sont pas recus)
function serverLost(){
    swal({
		title: "Erreur",
		text: "La connexion au serveur à été perdue !\nLes données ne peuvent pas être enregistrées ...",
		type: "warning",
		showCancelButton: false,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Ok"
		},  function(){
		// ne rien faire
    });
}
