import Page from '@wexample/symfony-loader/js/Class/Page';
import BannerService from "@wexample/symfony-design-system/js/Services/BannerService";
import PendingWritesService from '@wexample/symfony-loader/js/Services/PendingWritesService';

export default class extends Page {
  pageReady() {
    this.initLayoutBanner();
    this.initPendingWrites();
  }

  private initLayoutBanner() {
    const layoutBannerButton = this.el?.querySelector('.layout-banner-button') as HTMLElement;
    if (!layoutBannerButton) {
      return;
    }

    const bannerTypes = ['info', 'success', 'warning', 'error'];
    let bannerIndex = 0;
    layoutBannerButton.addEventListener('click', () => {
      const type = bannerTypes[bannerIndex % bannerTypes.length];
      const message =
        layoutBannerButton.getAttribute(`data-banner-message-${type}`) ||
        'Layout banner message.';
      bannerIndex += 1;
      (this.app.getServiceOrFail(BannerService) as any).show({
        type,
        message
      });
    });
  }

  // No request leaves the page: a timer stands for the server, so the banner
  // can be watched through a slow save, several at once, and a failure.
  private initPendingWrites() {
    const pendingWrites = this.app.getServiceOrFail(PendingWritesService) as PendingWritesService;
    const fakeWrite = (fail: () => boolean) => () =>
      new Promise<void>((resolve, reject) => {
        window.setTimeout(
          () => (fail() ? reject(new Error('Demo write failed.')) : resolve()),
          600 + Math.random() * 1200
        );
      });

    this.el?.querySelector('.pending-writes-save-button')?.addEventListener('click', () => {
      void pendingWrites.track(fakeWrite(() => false));
    });

    // Fails once, so the retry link in the banner then succeeds.
    this.el?.querySelector('.pending-writes-fail-button')?.addEventListener('click', () => {
      let attempts = 0;
      void pendingWrites.track(fakeWrite(() => attempts++ === 0)).catch(() => undefined);
    });
  }
}
