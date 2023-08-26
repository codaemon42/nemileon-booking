import {render, screen} from '@testing-library/react'
import Mail from './Mail'


test("testing Mail", () => {
    render(<Mail />)
    const element = screen.getByTestId('custom-element');
    expect(element.textContent).toBe("Mail")
})