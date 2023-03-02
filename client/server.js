const express = require('express');
const history = require('connect-history-api-fallback');
const serveStatic = require('serve-static');
const path = require('path');
const app = express()
const port = process.env.PORT || 4000;
const dir = path.join(__dirname, '/dist');

app.use(history({
	verbose: true
}));
app.use(serveStatic(dir, {index: 'index.html'}))
app.listen(port, () => {
	console.log(`Express web server started on port ${port}, environment ${process.env.NODE_ENV}, and serving content from ${dir}`);
});