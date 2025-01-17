import "./base.css";
import Alpine from "alpinejs";
import Feed from "./modules/Feed";

window.Alpine = Alpine;
Alpine.data("feed", Feed);
Alpine.start();
