import JsPDF from 'jspdf';

export const generatePdf = ({id}) => {
    const report = new JsPDF('portrait','pt','a4');
    report.html(document.querySelector(`#${id} .ant-card-body`)).then(() => {
        report.save('ticket.pdf');
    });
}

export default generatePdf