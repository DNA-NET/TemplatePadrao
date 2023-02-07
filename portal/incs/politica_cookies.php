
<!-- Política de Cookies -->

<style>
	.cookies-p {
		margin: 0px;
	}

	.cookies-container {
		color: #222;
		position: fixed;
		width: 100%;
		bottom: 2rem;
		z-index: 1000;
	}

	.cookies-content {
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
		background: #f2f2f2;
		width: 100%;
		max-width: 1120px;
		border-radius: 5px;
		padding: 1rem;
		margin: 0 auto;
		display: grid;
		grid-template-columns: 1fr auto;
		gap: 0.5rem;
		opacity: 0;
		transform: translateY(1rem);
		animation: slideUp 0.5s forwards;
	}

	.cookie-politics {
		text-decoration: none;
		cursor: pointer;
	}

	.cookie-politics:hover {
		text-decoration: underline;
	}

	@keyframes slideUp {
		to {
			transform: initial;
			opacity: initial;
		}
	}

	.cookies-pref label {
		margin-right: 1rem;
	}

	.cookies-save {
		grid-column: 2;
		grid-row: 1;
		background: #017d86;
		color: white;
		cursor: pointer;
		border: none;
		border-radius: 5px;
		padding: 1.2rem 1rem;
		font-size: 2rem;
	}

	.cookies-reject {
		grid-column: 2;
		grid-row: 2;
		background: #017d86;
		color: white;
		cursor: pointer;
		border: none;
		border-radius: 5px;
		padding: 1.2rem 1rem;
		font-size: 2rem;
	}

	@media (max-width: 500px) {
	.cookies-content {
		grid-template-columns: 1fr;
	}
	.cookies-save {
		grid-column: 1;
		grid-row: 1;
		}
	.cookies-reject {
		grid-column: 1;
		grid-row: 2;
		}
	}

	* {
		box-sizing: border-box;
	}

	.popup-wrapper {
		background: rgba(0,0,0,.5);
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		display: none;
	}

	.popup {
		width: 100%;
		max-width: 720px;
		margin: 10% auto;
		padding: 20px;
		background: white;
		position: relative;
		border-radius: 10px;
		overflow-y: auto;
		height: 500px;
	}

	.popup-link{
		color: white;
		cursor: pointer;
		padding: 6px 10px;
		text-decoration: none;
		background: #00445e;
	}

	.popup-close {
		position: absolute;
		top: 5px;
		right: 10px;
		cursor: pointer;
		border: solid 1px #00445e;
		background: #00445e;
		border-radius: 100px;
		color: white;
		padding: 5px 10px 5px 10px;
	}

	@media (max-width: 500px){
		.cookies-content {
			grid-template-columns: 1fr;
		}
		.cookies-save {
			grid-column: 1;
			grid-row: 3;
		}
		.cookies-continue {
			grid-column: 1;
			grid-row: 4;
		}
	}
</style>



<div class="cookies-container">
	<div class="cookies-content">

		<p class="cookies-p">Nós utilizamos cookies que nos ajudam a melhorar sua experiência de navegação e nos permitem medir como os visitantes se movimentam pelo site.</p>
		<p class="cookies-p">Ao continuar navegando, você concorda com a nossa <!--<a href="/ppsa/a-pre-sal-petroleo/politica-de-privacidade-e-de-cookies">--><strong>Política de Privacidade e de Cookies<!--</a>--></strong>.</p>
		<p class="cookies-p"><b>Este site não dá suporte ao navegador Internet Explorer<b></p>
		<div class="cookies-pref">
			<label><input class="hide" type="checkbox" checked data-function="aceito"></label>
			<label><input class="hide" type="checkbox" checked data-function="continuoSemConsentimento"></label>
		</div>
		<button class="cookies-save">Aceito</button>
		<button class="cookies-reject">Continue sem consentimento</button>
		</div>
	</div>	
</div>

<script>
	const button = document.querySelector('.cookie-politics');
	const popup = document.querySelector('.popup-wrapper');
	const closeButton = document.querySelector('.popup-close');

	button.addEventListener('click', () => {
		popup.style.display = 'block';
	});

	closeButton.addEventListener('click', () =>{
		popup.style.display = 'none';
	});

	popup.addEventListener('click', event => {
		const classNameOfClickedElement = event.target.classList[0];
		if(classNameOfClickedElement === 'popup-close' || classNameOfClickedElement === 'popup-link' || classNameOfClickedElement === 'popup-wrapper'){
			popup.style.display = 'none';
		}
	});

</script>

<script>
	function cookies(functions) {
		const container = document.querySelector('.cookies-container');
		const save = document.querySelector('.cookies-save');
		const reject = document.querySelector('.cookies-reject');
		if (!container || !save || !reject) return null;

		const localPref = JSON.parse(window.localStorage.getItem('cookies-pref'));
		if (localPref) activateFunctions(localPref);

		function getFormPref() {
			return [...document.querySelectorAll('[data-function]')]
			.filter((el) => el.checked)
			.map((el) => el.getAttribute('data-function'));
		}

		function activateFunctions(pref) {
			pref.forEach((f) => functions[f]());
			container.style.display = 'none';
			window.localStorage.setItem('cookies-pref', JSON.stringify(pref));
		}

		function handleSave() {
			const pref = getFormPref();
			activateFunctions(pref);
		}

		save.addEventListener('click', handleSave);
		reject.addEventListener('click', handleSave);
		}

		function aceito() {
		console.log('Função de aceitar');
		}

		function continuoSemConsentimento() {
		console.log('Função de Continuar sem consentimento');
		}

		cookies({
		aceito,
		continuoSemConsentimento,
		});
</script>