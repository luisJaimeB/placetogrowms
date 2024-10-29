import { createI18n } from 'vue-i18n';

const i18n = createI18n({
    globalInjection: true,
    locale: 'es',
    fallbackLocale: 'en',
    messages: {},
});

const loadMessages = async (locale) => {
    try {
        const response = await axios.get(`/lang/${locale}`);
        i18n.global.setLocaleMessage(locale, response.data.messages);
    } catch (error) {
        console.error('Error loading translations:', error);
    }
};

loadMessages('es');
export default i18n;
