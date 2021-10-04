module.exports = {
	testPathIgnorePatterns: ['/node_modules/'],
	moduleNameMapper: {
		".+\\.(css|styl|less|sass|scss)$": "<rootDir>/node_modules/jest-css-modules",
		".+\\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$": "<rootDir>/__mocks__/fileMock.js"
	},
	setupFilesAfterEnv: ['<rootDir>/jest-setup.js'],
	globals: {
		window: {},
		localized_data : {
				wpadcenter_pro_version : '5.2.3'
			},
		pro_ver_above_or_5_2_3 : true,
		
	},
	"automock": false,
  	"resetMocks": false,
}