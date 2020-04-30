document.addEventListener("DOMContentLoaded", function () {
	let successCatch = false;
	var escaping;
	var hunting = false;

	const body = document.querySelector('body');
	let userPokeball = parseInt(body.getAttribute("data-user-pokeball"));
	
	const pokemonMainContainer = document.querySelector(".pokemon-main-container")
	const cursorPokeball = document.querySelector('#cursor-pokeball');

	const huntBtn = document.querySelector('a.hunt-btn');
	const buyPokeball = document.querySelector('a.buy-pokeball-btn');
	const pokemon = document.querySelector('a.pokemon');
	let pokemonImage = document.querySelector('img.pokemon-image');
	
	const pokeballCatching = document.querySelector(".pokeball-catching");
	const pokeballCatch = document.querySelector(".pokeball-catch");

	const badge = document.querySelector(".badge-container");
	const badgeDifficulty = badge.querySelector(".badge-difficulty");
	const badgeImage = badge.querySelector(".badge-img img");

	let pokemonData;

	if (userPokeball >= 0) {

		huntBtn.classList.remove("disabled");

		if (buyPokeball) {
			buyPokeball.addEventListener("click", function (event) {
				buyPokeball.innerHTML = "Rechargement en cours...";
				buyPokeball.classList.add("disabled");
				event.preventDefault(); 
				
				const url = this.href;
				
				axios.post(url, {
				}).then(function (response) { 
					huntBtn.classList.remove("display-none");
					huntBtn.classList.remove("no-active");
					buyPokeball.classList.add("display-none");
					
					userPokeball = 6;
					$('.pokeball-container > div').removeClass('no-active');
					$('.pokeball-container > div').removeClass('display-none');
				 	buyPokeball.classList.remove("disabled");
					buyPokeball.innerHTML = "Acheter des pokeballs";
				});
			});
		}

		// Make pokeball cursor
		document.addEventListener("mousemove", function (event) {
			let x = event.clientX;
			let y = event.clientY;
			cursorPokeball.style.left = x + "px";
			cursorPokeball.style.top = y + "px";
		});

		if (huntBtn) {
			// Click on button 'Chasser'
			huntBtn.addEventListener("click", function (event) {
				event.preventDefault();
				body.style.cursor = "none";
				cursorPokeball.classList.remove('no-active');
				pokemonMainContainer.classList.remove('no-active');
				pokeballCatch.classList.remove("pokeball-animation-success");
				pokeballCatch.classList.remove("pokeball-animation-fail");
				
				// Remove a pokeball
				userPokeball -= 1;
				position = 6 - userPokeball;
				$('.pokeball-container div:nth-child('+position+')').addClass('no-active');

				setTimeout(() => {
					$('.pokeball-container  div:nth-child('+position+')').addClass('display-none');
				}, 1000);

				if (userPokeball == 0) {
					huntBtn.classList.add("no-active");
					setTimeout(() => {
						huntBtn.classList.add("display-none");
						buyPokeball.classList.remove("display-none");
					}, 1000);
				}

				const url = this.href;
				
				$(".pokeball-loader").css({ "width": "60px", "height": "60px" });
				
				axios.post(url, {
				}).then(function (response) {
					pokemonData = JSON.parse(response.data.content);
					// console.log(pokemonData);

					// change image pokemon
					pokemonImage.setAttribute("src", "/images/pokemons/" + pokemonData.idPokemon + ".png");
					pokemon.setAttribute("data-id", pokemonData.idPokemon);

					let escapeTime = (15 - pokemonData.difficulty) * 1000;

					setTimeout(() => {
						hunting = true;

						// Display game
						movePokemon('.pokemon', pokemonData.difficulty);
						
						let pokemonHeight = pokemonImage.offsetHeight;
						let pokemonWidth = pokemonImage.offsetWidth;

						if (pokemonHeight > pokemonWidth) {
							pokemonImage.style.height = "90px";
							pokemonImage.style.width = "auto";
						} else {
							pokemonImage.style.width = "90px";
							pokemonImage.style.height = "auto";
						}			
						
						pokemon.classList.remove("no-active");
						$(".pokemon").animate({ "opacity": 1 }, 500);
						
						escaping = setTimeout(() => {
							escape();
							return false;
						}, escapeTime);

					}, 20);
				})

				
			});
		}

		document.addEventListener("click", function (event) {
			// On click in game
			$('.pokemon').stop();
			if (!event.target.classList.contains("hunt-btn")) {
				if (hunting == true) {
					hunting = false;
				
					successCatch = false;

					pokemon.classList.add("disabled");
					
					// If click on pokemon
					if (event.target.classList.contains("pokemon-image")) {
						event.preventDefault();
						
						const url = pokemon.getAttribute("href");
						
						axios.post(url, {
							"pokemon_id": pokemon.getAttribute("data-id")
						}).then(function (response) {
							if (response.data.content) {	
								const newBadge = JSON.parse(response.data.content);
								
								setTimeout(() => {
									addBadge(newBadge)
								}, 5200);
							}
							
						});
						
						successCatch = true;

					} 
					isCatch(successCatch);
					clearTimeout(escaping);
					
				}
			}
		});

		function addBadge(newBadge) {
			const newDifficulty = newBadge.level + 1;
			const oldBadgeImage = badgeImage.getAttribute("data-image-path");
			const newBadgeImage = oldBadgeImage + newBadge.image;

			badgeImage.setAttribute("src", newBadgeImage);
			// badgeName.outerHTML = newBadge.name;
			$(".badge-name").text(newBadge.name);
			badgeDifficulty.outerHTML = newDifficulty;
			
			badge.classList.add("down-to-center");

			setTimeout(() => {
				badge.classList.remove("down-to-center");
			}, 7000);
		}

		function isCatch(isCatch = false) {
			$(".pokeball-loader").css({ "width": "0px", "height": "0px" });

			setTimeout(() => {
				pokeballCatching.classList.remove("no-active");
				
				if (isCatch) {	
					pokeballCatch.classList.add("pokeball-animation-success");
				} else {
					pokeballCatch.classList.add("pokeball-animation-fail");
				}
				
			}, 1000);

			setTimeout(() => {
				pokeballCatching.classList.add("no-active");
				$(".pokemon").animate({ "opacity" : 0 }, 500);
			}, 7000);
			
			setTimeout(() => {
				cursorPokeball.classList.add("no-active");
				pokemonMainContainer.classList.add("no-active");
				body.style.cursor = "inherit";
				pokemon.classList.add("no-active");
				pokemon.classList.remove("disabled");
			}, 7500);
		}

		function escape() {

			$('.pokemon').stop();

			pokeballCatching.classList.remove("no-active");
			pokeballCatching.classList.add("no-active");
			$(".pokemon").animate({ "opacity" : 0 }, 500);
			
			cursorPokeball.classList.add("no-active");
			pokemonMainContainer.classList.add("no-active");
			body.style.cursor = "inherit";
			pokemon.classList.add("no-active");
		}
		

		function makeNewPosition(){
			// Get viewport dimensions (remove the dimension of the div)
			var h = $(window).height() - 50;
			var w = $(window).width() - 50;
			
			var nh = Math.floor(Math.random() * h);
			var nw = Math.floor(Math.random() * w);
			
			return [nh,nw];    
		}

		function movePokemon(myclass, difficulty){
			var newq = makeNewPosition();
			duration = 1400 - (difficulty * 100);
			
			$(myclass).animate({ top: newq[0], left: newq[1] }, duration, function(){
			movePokemon(myclass, difficulty);        
			});
		};
	}
		
});