// Mock Data simulando MongoDB
const mockData = {
    businesses: [
        { id: 'b1', name: 'La Pizzería del Barrio', category: 'Italiana' },
        { id: 'b2', name: 'El Rincón del Sushi', category: 'Japonesa' },
        { id: 'b3', name: 'Tacos "El Pastor"', category: 'Mexicana' },
    ],
    reviews: [
        { id: 'r1', businessId: 'b1', user: 'Ana G.', rating: 5, comment: '¡La mejor pizza que he probado! La masa es perfecta y los ingredientes frescos.' },
        { id: 'r2', businessId: 'b1', user: 'Carlos M.', rating: 4, comment: 'Muy buena pizza, aunque el local es algo pequeño. El servicio fue rápido.' },
        { id: 'r3', businessId: 'b1', user: 'Lucía F.', rating: 3, comment: 'Estaba bien, pero nada espectacular. El queso un poco gomoso.' },
        { id: 'r4', businessId: 'b2', user: 'David S.', rating: 5, comment: 'Calidad impresionante. El pescado se deshace en la boca. Volveré sin duda.' },
        { id: 'r5', businessId: 'b2', user: 'Elena P.', rating: 4, comment: 'Buen sushi, pero algo caro para la cantidad. El ambiente es muy tranquilo.' },
        { id: 'r6', businessId: 'b3', user: 'Javier R.', rating: 5, comment: '¡Auténtico sabor mexicano! Los tacos al pastor son una locura.' },
        { id: 'r7', businessId: 'b3', user: 'María L.', rating: 5, comment: 'Barato, rápido y delicioso. Las salsas pican de verdad.' },
        { id: 'r8', businessId: 'b3', user: 'Pedro V.', rating: 4, comment: 'Muy ricos, aunque el sitio siempre está llenísimo. Vale la pena la espera.' },
    ],
    aiSummaries: {
        'b1': 'Los clientes valoran muy positivamente la calidad de la masa y la frescura de los ingredientes, considerándola una de las mejores pizzas de la zona. El servicio es generalmente rápido, aunque algunos mencionan que el local es pequeño y que la calidad puede ser inconsistente en ocasiones (e.g., el queso).',
        'b2': 'El consenso general es que la calidad del pescado es "impresionante" y la experiencia es de alta gama. Es una opción muy recomendada para amantes del sushi. La principal crítica es el precio, que algunos consideran elevado en relación a la cantidad, aunque el ambiente tranquilo compensa.',
        'b3': 'Este local es un éxito rotundo entre los clientes, que destacan su "auténtico sabor mexicano" y la excelencia de los tacos al pastor. Es una opción popular por ser "barata, rápida y deliciosa". El único punto negativo recurrente es que suele estar muy concurrido, lo que implica tiempos de espera.',
    }
};
