document.addEventListener('DOMContentLoaded', () => {
    if (typeof qz === 'undefined') {
        console.error('QZ Tray not loaded');
        return;
    }

    // DEV MODE SECURITY
    qz.security.setCertificatePromise(() => Promise.resolve(null));
    qz.security.setSignaturePromise(() => Promise.resolve(null));

    window.addEventListener('order-paid', async (event) => {
        const order = event.detail?.order;
        if (!order) return;

        try {
            if (!qz.websocket.isActive()) {
                await qz.websocket.connect();
            }

            const printer = await qz.printers.getDefault();

            const config = qz.configs.create(printer, {
                encoding: 'UTF-8'
            });

            const data = [
                '\x1B\x40',
                '\x1B\x61\x01',
                'SEUMPAMA BUNGA\n',
                '\x1B\x61\x00',
                '--------------------------\n',
            ];

            order.items.forEach(item => {
                data.push(`${item.name} ${item.qty} x ${item.price}\n`);
            });

            data.push(
                '--------------------------\n',
                `TOTAL : ${order.total}\n\n`,
                '\x1D\x56\x00'
            );

            await qz.print(config, data);

        } catch (err) {
            console.error(err);
            alert('Printer tidak terhubung');
        }
    });
});
