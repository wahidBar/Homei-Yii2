const { Sequelize } = require("sequelize");

// Option 2: Passing parameters separately (other dialects)
const sequelize = new Sequelize("db_homei", "root", "defrindr", {
  host: "localhost",
  dialect: "mariadb",
});

module.exports = sequelize;