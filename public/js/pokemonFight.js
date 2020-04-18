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

	let pokemonData;

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
			$('.pokeball-container').children().first().addClass('no-active');

			setTimeout(() => {
				$('.pokeball-container').children().first().remove();
			}, 5000);

			if (userPokeball == 0) {
				huntBtn.classList.add("no-active");
				setTimeout(() => {
					huntBtn.remove();
				}, 1000);
			}

			const url = this.href;
			onHunt = true;
			
			$(".pokeball-loader").css({ "width": "60px", "height": "60px" });
			
			axios.post(url, {
			}).then(function (response) {
				pokemonData = JSON.parse(response.data.content);
				console.log(pokemonData);

				// change image pokemon
				pokemonImage.setAttribute("src", "/images/pokemons/" + pokemonData.idPokemon + ".png");
				pokemon.setAttribute("data-id", pokemonData.idPokemon);

				setTimeout(() => {
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

				}, 20);
			})
		});
	}

	// On click in game
	document.addEventListener("click", function (event) {
		if ((onHunt == true) && (!event.target.classList.contains("hunt-btn"))) {
			successCatch = false;

			// If click on pokemon
			if (event.target.classList.contains("pokemon-image")) {
				event.preventDefault();

				const url = pokemon.getAttribute("href");
				
				axios.post(url, {
					"pokemon_id": pokemon.getAttribute("data-id")
				})
				
				successCatch = true;
			} 

			isCatch(successCatch);
		}
	});

	function isCatch(isCatch = false) {
		$('.pokemon').stop();
		$(".pokeball-loader").css({ "width": "0px", "height": "0px" });
		onHunt = false;

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
			pokemon.classList.add("no-active");
		}, 7000);

		setTimeout(() => {
			cursorPokeball.classList.add("no-active");
			pokemonMainContainer.classList.add("no-active");
			body.style.cursor = "inherit";
		}, 7500);
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
		duration = 1400 - (difficulty*100);
		
		$(myclass).animate({ top: newq[0], left: newq[1] }, duration, function(){
		movePokemon(myclass, difficulty);        
		});
		
	};
	

});