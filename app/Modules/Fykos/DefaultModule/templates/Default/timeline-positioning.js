document.addEventListener("DOMContentLoaded", function () {

	function positionLogo(dateStr, classStr, eventBoxId) {
		// Define the timeline start and end dates
		const startDate = new Date("2023-10-01");
		const endDate = new Date("2024-05-30");
		const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24);

		const eventDate = new Date(dateStr);
		const elapsedDays = (eventDate - startDate) / (1000 * 60 * 60 * 24);

		// Calculate the position for the logo
		const positionPercentage = (elapsedDays / totalDays) * 100;

		// Create the logo element and set its position
		const logoElem = document.createElement("img");
		logoElem.src = "fykos_symbol_white.png"; // Replace with the path to your logo
		logoElem.classList.add(classStr);

		if (window.innerWidth <= 768) {  /* Small screen */
			logoElem.style.top = `${positionPercentage}%`;
		} else {  /* Large screen */
			logoElem.style.left = `${positionPercentage}%`;
		}

		logoElem.dataset.eventBox = eventBoxId;
		// Append the logo to the body (or the container div you wish)
		document.querySelector('.timeline-container').appendChild(logoElem);
	}

	// Position logos for each event
	let dates_events = ["2023-11-03", "2023-11-06", "2023-11-22", "2024-02-16"];
	let dates_series = ["2023-10-10", "2023-11-21", "2024-01-02", "2024-02-27", "2024-04-09", "2024-05-14"];

	dates_events.forEach(function (num, index) {
		positionLogo(num, "logo-event", `event-${index + 1}`);
	});

	dates_series.forEach(function (num, index) {
		positionLogo(num, "logo-series", `series-${index + 1}`);
	});

	document.querySelectorAll('.logo-event, .logo-series').forEach(function (logo) {
		logo.addEventListener('mouseover', function () {
			const associatedEventBox = document.getElementById(logo.dataset.eventBox);
			if (associatedEventBox) {
				associatedEventBox.classList.add('active');
			}
		});

		logo.addEventListener('mouseout', function () {
			const associatedEventBox = document.getElementById(logo.dataset.eventBox);
			if (associatedEventBox) {
				associatedEventBox.classList.remove('active');
			}
		});
	});
});

