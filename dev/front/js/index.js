import axette from "axette";
import "./import/darkmode.js";
import { loadAds } from './import/load-ads.js';
import "./import/popup.js";

axette.init()
axette.fixURL()
loadAds();
