/* Copyright (C) 2007 - 2010 YOOtheme GmbH */

var YOOTemplate = {
		
	start: function() {

		/* Match height of div tags */
		YOOTemplate.matchHeights();

		/* Accordion menu */
		new YOOAccordionMenu('div#middle ul.menu li.toggler', 'ul.accordion', { accordion: 'slide' });

		/* Dropdown menu */
		var dropdown = new YOODropdownMenu('menu', { mode: 'slide', dropdownSelector: 'div.dropdown', transition: Fx.Transitions.Expo.easeOut });
		//dropdown.matchHeight();

		/* set hover color */
		var hoverColorMenu;
		var leaveColorMenu;
		var hoverColorSubmenu;
		var leaveColor1Submenu;
		var leaveColor2Submenu;
		switch (YtSettings.color) {
			case 'blogging':
				hoverColorMenu = '#D9D3CA';
				leaveColorMenu ='#ffffff';
				hoverColorSubmenu = '#483030';
				leaveColor1Submenu ='#EBE7E4';
				leaveColor2Submenu ='#804640';
				break;
			case 'travel':
				hoverColorMenu = '#DCD2C3';
				leaveColorMenu ='#ffffff';
				hoverColorSubmenu = '#3E8ABA';
				leaveColor1Submenu ='#FAF5E9';
				leaveColor2Submenu ='#207ab2';
				break;
			case 'sports':
				hoverColorMenu = '#D7D4D4';
				leaveColorMenu ='#ffffff';
				hoverColorSubmenu = '#3B3939';
				leaveColor1Submenu ='#e9e8e7';
				leaveColor2Submenu ='#525050';
				break;
			case 'adventure':
				hoverColorMenu = '#dbdacf';
				leaveColorMenu ='#fffffa';
				hoverColorSubmenu = '#AA9777';
				leaveColor1Submenu ='#eeece0';
				leaveColor2Submenu ='#675543';
				break;
			default:
				hoverColorMenu = '#EBEBEB';
				leaveColorMenu ='#FFFFFF';
				hoverColorSubmenu = '#8A9CA7';
				leaveColor1Submenu ='#F5F5F5';
				leaveColor2Submenu ='#5f6b7c';
		}

		/* Morph: main menu - level1 (tab) */
		var submenuEnter = { 'padding-top': '0px', 'height': '50px' };
		var submenuLeave = { 'padding-top': '3px', 'height': '47px' };

		new YOOMorph('#menu a.level1 span.bg, #menu span.level1 span.bg', submenuEnter, submenuLeave,
			{ transition: Fx.Transitions.expoOut, duration: 200 },
			{ transition: Fx.Transitions.sineIn, duration: 200 });

		/* Morph: main menu - level2 (color) */
		var menuEnter = { 'background-color': hoverColorMenu};
		var menuLeave = { 'background-color': leaveColorMenu};

		new YOOMorph('div#menu .hover-box1', menuEnter, menuLeave,
			{ transition: Fx.Transitions.linear, duration: 0, ignore: 'div#menu li li.separator .hover-box1, div#menu .mod-dropdown .hover-box1' },
			{ transition: Fx.Transitions.Quart.easeOut, duration: 600 });

		/* Morph: mod-rounded sub menu - level1 (bg) */
		var submenuEnter = { 'background-color': hoverColorSubmenu };
		var submenuLeave = { 'background-color': leaveColor1Submenu };

		new YOOMorph('div.mod-rounded ul.menu a.level1, div.mod-rounded ul.menu span.level1', submenuEnter, submenuLeave,
			{ transition: Fx.Transitions.expoOut, duration: 0 },
			{ transition: Fx.Transitions.Quint.easeOut, duration: 200 });

		/* Morph: mod-rounded sub menu - level1 (color) */
		var submenuEnter = { 'color': '#ffffff' };
		var submenuLeave = { 'color': leaveColor2Submenu };

		new YOOMorph('div.mod-rounded ul.menu a.level1 span.bg, div.mod-rounded ul.menu span.level1 span.bg', submenuEnter, submenuLeave,
			{ transition: Fx.Transitions.expoOut, duration: 0 },
			{ transition: Fx.Transitions.Quint.easeOut, duration: 200 });

		/* Smoothscroll */
		new SmoothScroll({ duration: 500, transition: Fx.Transitions.Expo.easeOut });
	},

	/* Match height of div tags */
	matchHeights: function() {
		YOOBase.matchHeight('div.headerbox div.deepest', 20);
		YOOBase.matchHeight('div.topbox div.deepest', 20);
		YOOBase.matchHeight('div.bottombox div.deepest', 20);
		YOOBase.matchHeight('div.maintopbox div.deepest', 20);
		YOOBase.matchHeight('div.mainbottombox div.deepest', 20);
		YOOBase.matchHeight('div.contenttopbox div.deepest', 20);
		YOOBase.matchHeight('div.contentbottombox div.deepest', 20);
	}

};

/* Add functions on window load */
window.addEvent('domready', YOOTemplate.start);
