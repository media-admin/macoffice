//
// tabs.js
//

jQuery(document).ready(function(){

	// Der Button wird mit JavaScript erzeugt und vor dem Ende des body eingebunden.
	document.addEventListener("click", e => {  if (e.target.parentElement.classList.contains("tab-title")  ) { redTab(e) }  });

	[...document.querySelectorAll("div.tab-area div")].forEach((tabTile, index) => {
			tabTile.classList.toggle('active', index == 0)
		});
	[...document.querySelectorAll(".tab-content")].forEach((tabcontent, index) => {
		tabcontent.classList.toggle('active', index == 0)
		});
	function redTab(e) {
		let tabTiles = [...document.querySelectorAll("div.tab-area div")];
		let tabcontents = [...document.querySelectorAll(".tab-content")];
		let activeTabIndex = tabTiles.findIndex(tab => { return tab == e.target.parentElement })
		tabTiles.forEach((tabTile, index) => {
			tabTile.classList.toggle('active', index === activeTabIndex)
		})
		tabcontents.forEach((tabcontent, index) => {
		tabcontent.classList.toggle('active', index === activeTabIndex)
		})
	}

});