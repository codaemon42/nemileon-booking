import { useEffect, useState } from 'react'

const useWindowSize = () => {

    const [size, setSize] = useState(null);
    const [width, setWidth] = useState(window.innerWidth);

    useEffect(() => {
      if(!size){
        hanleResize();
      }
      const resize = window.addEventListener('resize', hanleResize)
    
      return () => {
        window.removeEventListener('resize', hanleResize)
      }
    }, [width])

    const hanleResize = () => {
        // console.log({ev});
        const windowWidth = window.innerWidth;
        setWidth(windowWidth);

        if(windowWidth >= 900){
            setSize('lg');
        } 
        else if(windowWidth < 900 && windowWidth > 678){
            setSize('md')
        }
        else if( windowWidth <= 678 && windowWidth >= 420 ){
            setSize('sm')
        }
        else {
            setSize('xs')
        }
    }
    
  return {size, width}
}

export default useWindowSize