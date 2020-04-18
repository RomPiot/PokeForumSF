document.addEventListener("DOMContentLoaded", function () {
	let onHunt = false;
	let successCatch = false;
	
	const body = document.querySelector('body');
	const userId = body.getAttribute("data-user-id");
	let userPokeball = body.getAttribute("data-user-pokeball");
	
	const pokemonMainContainer = document.querySelector(".pokemon-main-container")
	const cursorPokeball = document.querySelector('#cursor-pokeball');

	const huntBtn = document.querySelector('a.hunt-btn');
	const pokemon = document.querySelector('a.pokemon');
	let pokemonImage = document.querySelector('img.pokemon-image');
	
	const pokeballCatching = document.querySelector(".pokeball-catching");
	const pokeballCatch = document.querySelector(".pokeball-catch");
	const pokeballCatchBtn = document.querySelector(".pokeball-button");


	let pokemonData;

	document.addEventListener("mousemove", function (event) {
		let x = event.clientX;
		let y = event.clientY;
		cursorPokeball.style.left = x + "px";
		cursorPokeball.style.top = y + "px";
	});

	if (huntBtn) {
		
		// Click on button 'Chasser un pokemon'
		huntBtn.addEventListener("click", function (event) {
			event.preventDefault();
			body.style.cursor = "none";
			cursorPokeball.classList.remove('display-none');
			pokemonMainContainer.classList.remove('display-none');
			const url = this.href;
			onHunt = true;
			
			$(".pokeball-loader").css({ "width": "60px", "height": "60px" });
			
			axios.post(url, {
				"user_id": userId
			}).then(function(response) {
				// Remove a pokeball
				$('.pokeball-container').children().last().remove();
				userPokeball -= 1;
				
				pokemonData = JSON.parse(response.data.content);
				// console.log(pokemonData);

				let idPokemon = pokemonData.idPokemon;

				// change image pokemon
				pokemonImage.setAttribute("src", "/images/pokemons/" + pokemonData.idPokemon + ".png");
				pokemon.setAttribute("data-id", pokemonData.idPokemon);

				if (userPokeball == 0) {
					huntBtn.remove();
				}

				setTimeout(() => {
					// Display game
					$(".pokemon-main-container").fadeIn("0.5");
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
				}, 20);
			})
		});
	}

	// On click in game
	document.addEventListener("click", function (event) {
		if (onHunt == true) {
			successCatch = false;

			if (!event.target.classList.contains("hunt-btn")) {
				cursorPokeball.style.zIndex = 1000;
			}
			// If click on pokemon
			if (event.target.classList.contains("pokemon-image")) {
				event.preventDefault();

				const url = pokemon.getAttribute("href");
				
				axios.post(url, {
					"user_id": userId,
					"pokemon_id": pokemon.getAttribute("data-id")
				}).then(function(response) {
					if (response.data == true) {
						successCatch = true;
					}
				})
			} 

			if (!event.target.classList.contains("hunt-btn")) {
				$('.pokemon').stop();

				$(".pokeball-loader").css({ "width": "0px", "height": "0px" });
				
				setTimeout(() => {
					pokeballCatching.classList.remove("display-none");
					$(".pokeball-catching").animate({ "opacity": 1 }, 400);
					pokeballCatch.classList.add("pokeball-animation");
					
					if (successCatch == true) {	
						setTimeout(() => {
							pokeballCatchBtn.classList.add("pokeball-success");
						}, 1500);
					}
					
					setTimeout(() => {
						$(".pokemon-main-container").animate({ "opacity": "0" }, 700);
					}, 5000);

					setTimeout(() => {
						onHunt = false;
						cursorPokeball.style.zIndex = 998;
						pokemonMainContainer.classList.add("display-none");
						pokeballCatchBtn.classList.remove("pokeball-success");
						pokemonMainContainer.style.opacity = 1;
						cursorPokeball.style.opacity = 1;
						body.style.cursor = "inherit";
						pokeballCatching.classList.add("display-none");
						cursorPokeball.classList.add('display-none');
					}, 6000);
				}, 1000);
			}
		}
		
	});
	

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
		duration = 1600 - (difficulty*100);
		
		$(myclass).animate({ top: newq[0], left: newq[1] }, duration, function(){
		movePokemon(myclass, difficulty);        
		});
		
	};
	

});