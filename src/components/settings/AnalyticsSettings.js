import { FireFilled } from '@ant-design/icons'
import { Result } from 'antd'
import React from 'react'

const AnalyticsSettings = () => {


    return (
        <>
            <Result
                icon={<FireFilled />}
                status='error'
                title="PRO VERSION (coming soon)"
            />
        </>
    )
}

export default AnalyticsSettings