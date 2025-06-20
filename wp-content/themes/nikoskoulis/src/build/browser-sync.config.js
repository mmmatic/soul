module.exports = {
	"proxy": "nikoskoulis.local",
	"host": 'nikoskoulis.local',
	"port": 3002,
	"files": ["./css/*.min.css", "./js/*.min.js", "./**/*.php"],
	"injectChanges": true,
	"open": false,
	"notify": false
};
