'use strict';

const pkg = require( '../../package.json' );
const year = new Date().getFullYear();

function getBanner( pluginFilename ) {
	return `/*!
 * Nikos Koulis theme by SOUL Creative Agency
 */`;
}

module.exports = getBanner;
