import UIkit from "uikit"
import Icons from "uikit/dist/js/uikit-icons"
import NetteForms from "../../../vendor/nette/forms/src/assets/netteForms.js"
import Choices from "choices.js"
import flatpickr from "flatpickr"
import { Czech } from "flatpickr/dist/l10n/cs"
import {
  notificationFailure,
  notificationSuccess,
  choicesOptions
} from "./imports/settings"
import { toggle } from "./imports/helpers"
import "./../../../app/modules/Admin/components/DropUpload/DropUpload"
import "./imports/upload"

// TODO: https://github.com/babel/babelify#why-arent-files-in-node_modules-being-transformed

// UIKit
UIkit.use(Icons)

// nette forms
NetteForms.initOnLoad()

document.addEventListener(`DOMContentLoaded`, () => {

  // sortable
  UIkit.util.on(
    ".js-sortable",
    "moved",
    ({
      target: {
        children,
        dataset: { callback }
      }
    }) => {
      const idList = [...children].map(el => el.id)
      const req = new XMLHttpRequest()
      req.open("GET", `${callback}&idList=${idList}`)
      req.addEventListener("load", () => {
        if (req.readyState === 4 && req.status === 200) {
          return UIkit.notification(notificationSuccess)
        }
        return UIkit.notification(notificationFailure)
      })
      req.addEventListener("error", () =>
        UIkit.notification(notificationFailure)
      )
      req.send()
    }
  )

  // multiselect
  const multies = document.querySelectorAll(`.js-select`)
  multies.forEach(multi => new Choices(multi, choicesOptions(multi)))

  // date picker
  flatpickr(`.js-date`, {
    locale: Czech,
    enableTime: true,
    // dateFormat: "d. m. Y. H:i",
    // eslint-disable-next-line camelcase
    time_24hr: true
  })

  // toggle logic
  const togglers = document.querySelectorAll(`[data-toggler]`)
  ;[...togglers].forEach(toggler =>
    toggler.addEventListener(`change`, () => toggle(togglers))
  )
  toggle(togglers)
})
