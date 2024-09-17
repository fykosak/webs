import React, { createContext, useContext, useState } from 'react';

type Lang = 'cs' | 'en';

interface Translations {
  [key: string]: {
    cs: string;
    en: string;
  };
}

const TranslatorContext = createContext<{
  translate: (key: string, replacements?: { [key: string]: string | number }) => React.ReactNode;
  translateText: (key: string, replacements?: { [key: string]: string | number }) => string;
  language: Lang;
}>({
  translate: () => '',
  translateText: () => '',
  language: 'en',
});

const translations: Translations = {
    categoryRank: {
      cs: 'Pořadí<br>v kategorii',
      en: 'Category<br>Rank',
    },
    name: {
      cs: 'Jméno',
      en: 'Name',
    },
    school: {
      cs: 'Škola',
      en: 'School',
    },
    totalPoints: {
      cs: 'Celkem<br>bodů',
      en: 'Total<br>Points',
    },
    selectYear: {
      cs: 'Vybrat ročník',
      en: 'Select Year',
    },
    categoryLabel: {
      cs: 'Kategorie {categoryNumber}. ročníků',
      en: 'Category of high-school year {categoryNumber}',
    },
    maxNumPointsHeader: {
      cs: 'Maximální počet bodů',
      en: 'Max Number of Points',
    },
    sumOneToThree: {
      cs: 's1-3',
      en: 's1-3',
    },
    sumFourToSix: {
      cs: 's4-6',
      en: 's4-6',
    },
    allCategories: {
      cs: 'Všechny kategorie',
      en: 'All Categories',
    },
    totalRank: {
      cs: 'Celkové<br>pořadí',
      en: 'Total<br>Rank',
    },
    category: {
      cs: 'Kategorie',
      en: 'Category',
    },
  };

export class Translator {
  private language: Lang;

  constructor() {
    const htmlElement = document.getElementsByTagName('html')[0];
    this.language = (htmlElement.lang as Lang) || 'en';
  }

  private applyReplacements(text: string, replacements: { [key: string]: string | number }): string {
    return Object.entries(replacements).reduce((acc, [placeholder, value]) => {
      return acc.replace(new RegExp(`{${placeholder}}`, 'g'), String(value));
    }, text);
  }

  public translate(key: string, replacements: { [key: string]: string | number } = {}): React.ReactNode {
    const text = translations[key]?.[this.language] || key;
    const replacedText = this.applyReplacements(text, replacements);
    
    return <span dangerouslySetInnerHTML={{ __html: replacedText }} />;
  }

  public translateText(key: string, replacements: { [key: string]: string | number } = {}): string {
    const text = translations[key]?.[this.language] || key;
    return this.applyReplacements(text, replacements).replace(/<br\s*\/?>/gi, '\n');
  }

  public getLanguage(): Lang {
    return this.language;
  }
}

export const TranslatorProvider: React.FC = ({ children }) => {
  const [translator] = useState(() => new Translator());

  const contextValue = {
    translate: translator.translate.bind(translator),
    translateText: translator.translateText.bind(translator),
    language: translator.getLanguage(),
  };

  return (
    <TranslatorContext.Provider value={contextValue}>
      {children}
    </TranslatorContext.Provider>
  );
};

export const useTranslator = () => useContext(TranslatorContext);
