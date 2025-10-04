class CountdownComponent {
  private readonly id: string;
  private readonly countdownTo: Date;
  private readonly lang: string;

  private getTranslations: Record<string, ((n: number) => string)[]> = {
    en: [
      (n) => n === 1 ? 'day' : 'days',
      (n) => n === 1 ? 'hour' : 'hours',
      (n) => n === 1 ? 'minute' : 'minutes',
      (n) => n === 1 ? 'second' : 'seconds',
    ],
    cs: [
      (n) => n === 1 ? 'den' : n >= 2 && n <= 4 ? 'dny' : 'dní',
      (n) => n === 1 ? 'hodina' : n >= 2 && n <= 4 ? 'hodiny' : 'hodin',
      (n) => n === 1 ? 'minuta' : n >= 2 && n <= 4 ? 'minuty' : 'minut',
      (n) => n === 1 ? 'sekunda' : n >= 2 && n <= 4 ? 'sekundy' : 'sekund',
    ]
  };

  private tick() {
    const now = new Date().getTime();
    const dist = (this.countdownTo.getTime() - now)/1000;
    const days = Math.floor(dist/86400)
    const hours = Math.floor((dist-days*86400)/3600);
    const mins = Math.floor(((dist-days*86400)-hours*3600)/60);
    const secs = Math.floor((dist-days*86400-hours*3600-mins*60));

    const date = [days, hours, mins, secs];
    const idElements = ['day', 'hour', 'minute', 'second'];

    if (dist > 0){
      date.forEach((value, index) => {
        document.getElementById('countdown-' + this.id + '-' + idElements[index]).innerText = String(value).padStart(2, '0');
        document.getElementById('countdown-' + this.id + '-' + idElements[index] + '-text').innerText = this.getTranslations[this.lang][index](value);
      });
    }
  }

  public constructor(countdownTo: Date, id: string, lang: string) {
    this.countdownTo = countdownTo;
    this.id = id;
    this.lang = lang;

    this.tick();
    setInterval(() => this.tick(), 1000);
  }
}

// @ts-ignore
window.CountdownComponent = CountdownComponent;
