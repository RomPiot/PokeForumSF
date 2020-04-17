document.addEventListener("DOMContentLoaded", function () {
	let onHunt = false;
	
	const body = document.querySelector('body');
	const userId = body.getAttribute("data-user-id");
	let userPokeball = body.getAttribute("data-user-pokeball");
	
	const cursorPokeball = document.querySelector('#cursor-pokeball');

	const huntBtn = document.querySelector('a.hunt-btn');
	const pokemon = document.querySelector('a.pokemon');
	let pokemonImage = document.querySelector('img.pokemon-image');
	
	let pokemonData;

	document.addEventListener("mousemove", function (event) {
		let x = event.clientX;
		let y = event.clientY;
		cursorPokeball.style.left = x + "px";
		cursorPokeball.style.top = y + "px";
	});

	// Click on button 'Chasser un pokemon'
	huntBtn.addEventListener("click", function (event) {
		event.preventDefault();
		body.style.cursor = "none";
		cursorPokeball.classList.remove('display-none');
		
		const url = this.href;
		onHunt = true;
		
		$(".pokeball-loader").css({ "width": "60px", "height": "60px" });
		
		axios.post(url, {
			"user_id": userId
		}).then(function(response) {
			// Remove a pokeball
			$('.pokeball-container').children().last().remove();
			userPokeball -= 1;
			console.log(userPokeball);
			
			pokemonData = JSON.parse(response.data.content);
			console.log(pokemonData);

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

	// On click in game
	document.addEventListener("click", function (event) {
		if (onHunt == true) {
			// If click on pokemon
			if (event.target.classList.contains("pokemon-image")) {
				event.preventDefault();

				const url = pokemon.getAttribute("href");
				
				axios.post(url, {
					"user_id": userId,
					"pokemon_id": pokemon.getAttribute("data-id")
				}).then(function(response) {
						console.log(response);
				})
			} 

			if (!event.target.classList.contains("hunt-btn")) {
				$('.pokemon').stop();

				$(".pokeball-loader").css({ "width": "0px", "height": "0px" });
				
				setTimeout(() => {
					onHunt = false;
					$(".pokemon-main-container").fadeOut("0.5");
					body.style.cursor = "inherit";
					cursorPokeball.classList.add('display-none');
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
		duration = 1400 - (difficulty*100);
		
		$(myclass).animate({ top: newq[0], left: newq[1] }, duration, function(){
		movePokemon(myclass, difficulty);        
		});
		
	};
	

});