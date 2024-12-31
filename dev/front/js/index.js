import axette from "axette";
import "./import/darkmode.js";
import { loadAds } from './import/load-ads.js';
import "./import/popup.js";
import "./../../../app/modules/Front/components/MessageForm/MessageForm.js"
import { copyLink } from "./import/created.js";

axette.init();
axette.fixURL();
loadAds();

const messageLink = document.getElementById(`messageLink`);
if (messageLink) {
    messageLink.addEventListener(`click`, copyLink, false);
}
