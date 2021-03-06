<?php include('../view/header.php'); ?>

<link rel="stylesheet" href="../view/css/planning.css" type="text/css" />
<link href='../fullcalendar/fullcalendar.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<?php require_once '../view/baseMenuFnct.php'; ?>

<div class="div-principal">
	<div id='calendar'></div>
	<button id="boutonaddevenement" class="btn-md btn-primary" onClick="afficheModifieEvenement(null)">Ajouter un évènement</button>
</div>

<div id="fond-popup"></div>
<div id="popup"></div>

<?php include('../view/scripts.php') ?>

<!-- Script du fullcalendar -->
<script type="text/javascript" src='../fullcalendar/lib/moment.min.js'></script>
<script type="text/javascript" src='../fullcalendar/fullcalendar.min.js'></script>
<script type="text/javascript" src='../fullcalendar/locale/fr.js'></script>

<!-- timepiker <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script> -->

<script type="text/javascript" src='../view/js/planning.js'></script>

<!-- C'est le script qui gère de mettre la view -->
<script type="text/javascript">
	$(document).ready(function() {

		$('#calendar').fullCalendar({

			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,listDay' // agenda/list/basic
			},

			views: {
				listDay: { buttonText: 'jour' },
				agendaWeek: { buttonText: 'semaine',
							  allDaySlot: false}
			},
			
			nextDayThreshold: '05:00:00',
			defaultView: 'month',
			navLinks: true, // can click day/week names to navigate views
			editable: true, // planning modifiable
			eventLimit: true, // pas de nombre limit d'évenement affichable

			events: [
				<?php
					/* format
						id: nombre,
						title: '',
						start: 'Y-m-d H:i:s',
						end: 'Y-m-d H:i:s'
					*/

					if (isset($evenements)){
						foreach ($evenements as $evenement) {
				?>
							{
							id: <?= $evenement->getId() ?>,
							title: '<?= $evenement->getDescription() ?>',
							start: '<?= $evenement->getStart()->format('Y-m-d H:i:s') ?>',
							end: '<?= $evenement->getEnd()->format('Y-m-d H:i:s') ?>'
							},
				<?php
						}
					}
				?>

			],

			// fonction quand on click sur un évènement
			// utilisé pour quand l'utilisateur veut modifier un événement
			eventClick: function(event, element) {
				afficheModifieEvenement(event);
			},

			// fonction quand un évenemnt est changé de place
			eventDrop: function(event, delta, revertFunc) {
				modifEvenement(event, null);
			},

			// fonction quand un événement à sa durée qui est modifié
			eventResize: function(event, delta, revertFunc) {
				modifEvenement(event, null);
			}
		});
	});
</script>

<?php include('../view/footer.php') ?>
