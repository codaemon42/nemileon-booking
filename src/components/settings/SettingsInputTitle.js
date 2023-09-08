import { InfoCircleFilled } from '@ant-design/icons'
import { Space, Tooltip } from 'antd'
import React from 'react'

const SettingsInputTitle = ({title, children}) => {
    
    return (
        <>
            <Space >
                <div>{title}</div>
                <Tooltip title={children}  placement="right" color='cyan'>
                    <InfoCircleFilled />
                </Tooltip>
            </Space>
        </>
    )
}

export default SettingsInputTitle