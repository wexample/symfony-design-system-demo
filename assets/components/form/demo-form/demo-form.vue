<script>

import Form from "@wexample/symfony-design-system/components/_abstract/form/form.vue";
import TextInput from "@wexample/symfony-design-system/components/form/text-input/text-input.vue";
import TextareaInput from "@wexample/symfony-design-system/components/form/textarea-input/textarea-input.vue";
import RadioInput from "@wexample/symfony-design-system/components/form/radio-input/radio-input.vue";
import SwitchInput from "@wexample/symfony-design-system/components/form/switch-input/switch-input.vue";
import SubmitInput from "@wexample/symfony-design-system/components/form/submit-input/submit-input.vue";
import DemoStampsInput from "../demo-stamps-input/demo-stamps-input.vue";
import SearchSelectInput from "@wexample/symfony-design-system/components/form/search-select-input/search-select-input.vue";
import RoutingService from "@wexample/symfony-loader/js/Services/RoutingService";

export default {
  extends: Form,
  components: {
    TextInput,
    TextareaInput,
    RadioInput,
    SwitchInput,
    SubmitInput,
    DemoStampsInput,
    SearchSelectInput
  },

  props: {
    // The route answering the form, handed by the page in full.
    submitUrl: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      textSimple: '',
      textArea: '',
      stamps: ['urgent'],
      country: 'IT',
      password: '',
      emoji: '',
      number: '',
      email: '',
      url: '',
      date: '',
      datetime: '',
      time: '',
      file: '',
      radioChoice: '',
      switchValue: false,
      behavior: 'default',
      submitEndpoint: this.submitUrl,
      // A route of the page, not of the api.
      submitThroughApi: false,
      formSubmitted: false,
    };
  },

  computed: {
    // Where the countries are asked, a route of the demo.
    countriesUrl() {
      return this.app.getServiceOrFail(RoutingService).generate('wexample_design_system_generic_form_countries');
    },

    // Said in words here: `@vue` names the domain of whoever translates, and
    // the radio would read its own rather than this form's.
    radioChoiceOptions() {
      return ['option_a', 'option_b', 'option_c'].map((value) => ({
        value,
        label: this.trans(`@vue::field.radio_choice.choice.${value}.label`),
      }));
    },
  },

  methods: {
    buildSubmitPayload() {
      return {
        text_simple: this.textSimple,
        text_area: this.textArea,
        stamps: this.stamps.join(', '),
        country: this.country,
        password: this.password,
        emoji: this.emoji,
        number: this.number,
        email: this.email,
        url: this.url,
        date: this.date,
        datetime: this.datetime,
        time: this.time,
        file: this.file,
        radio_choice: this.radioChoice,
        switch: this.switchValue,
        behavior: this.behavior,
      };
    },

    onBeforeSubmit() {
      if (this.behavior === 'js') {
        this.formIsSubmitting = true;
        this.formController.beginSubmit();

        const confirmService = this.app?.getService?.('confirm');
        if (confirmService) {
          confirmService.confirmToast({
            title: this.trans('@vue::toast.js_action.title'),
            message: this.trans('@vue::toast.js_action.message'),
            actions: [
              { key: 'r', value: 'reactivate', label: this.trans('@vue::toast.js_action.reactivate'), role: 'primary' },
              { key: 'd', value: 'dismiss', label: this.trans('@vue::toast.js_action.dismiss'), role: 'secondary' },
            ],
          }).then((response) => {
            if (response === 'reactivate') {
              this.formIsSubmitting = false;
              this.formController.endSubmit();
            }
          });
        }
        return false;
      }
      return true;
    },

    onApiSubmitSuccess(response) {
      if (response?.type === 'redirect' && response?.url) {
        window.location.href = response.url;
        return;
      }

      if (response?.type === 'success') {
        this.formSubmitted = true;
      }
    },
  }
}

</script>
