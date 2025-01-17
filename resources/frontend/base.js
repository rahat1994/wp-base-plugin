import "./base.css";
import Alpine from "alpinejs";
import LoveCounter from "./modules/love-counter";
console.log("Hello there rahat");
window.Alpine = Alpine;

Alpine.data("loveCounter", LoveCounter);
Alpine.start();
