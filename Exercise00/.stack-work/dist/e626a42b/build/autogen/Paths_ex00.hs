{-# LANGUAGE CPP #-}
{-# LANGUAGE NoRebindableSyntax #-}
{-# OPTIONS_GHC -fno-warn-missing-import-lists #-}
module Paths_ex00 (
    version,
    getBinDir, getLibDir, getDynLibDir, getDataDir, getLibexecDir,
    getDataFileName, getSysconfDir
  ) where

import qualified Control.Exception as Exception
import Data.Version (Version(..))
import System.Environment (getEnv)
import Prelude

#if defined(VERSION_base)

#if MIN_VERSION_base(4,0,0)
catchIO :: IO a -> (Exception.IOException -> IO a) -> IO a
#else
catchIO :: IO a -> (Exception.Exception -> IO a) -> IO a
#endif

#else
catchIO :: IO a -> (Exception.IOException -> IO a) -> IO a
#endif
catchIO = Exception.catch

version :: Version
version = Version [0,1,0,0] []
bindir, libdir, dynlibdir, datadir, libexecdir, sysconfdir :: FilePath

bindir     = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\bin"
libdir     = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\lib\\x86_64-windows-ghc-8.6.4\\ex00-0.1.0.0-CL1edgu8XXmJxSLN8u8iud"
dynlibdir  = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\lib\\x86_64-windows-ghc-8.6.4"
datadir    = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\share\\x86_64-windows-ghc-8.6.4\\ex00-0.1.0.0"
libexecdir = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\libexec\\x86_64-windows-ghc-8.6.4\\ex00-0.1.0.0"
sysconfdir = "C:\\Users\\micha\\Documents\\GitHub\\VisualStudioCode\\Exercise00\\.stack-work\\install\\3d547ec3\\etc"

getBinDir, getLibDir, getDynLibDir, getDataDir, getLibexecDir, getSysconfDir :: IO FilePath
getBinDir = catchIO (getEnv "ex00_bindir") (\_ -> return bindir)
getLibDir = catchIO (getEnv "ex00_libdir") (\_ -> return libdir)
getDynLibDir = catchIO (getEnv "ex00_dynlibdir") (\_ -> return dynlibdir)
getDataDir = catchIO (getEnv "ex00_datadir") (\_ -> return datadir)
getLibexecDir = catchIO (getEnv "ex00_libexecdir") (\_ -> return libexecdir)
getSysconfDir = catchIO (getEnv "ex00_sysconfdir") (\_ -> return sysconfdir)

getDataFileName :: FilePath -> IO FilePath
getDataFileName name = do
  dir <- getDataDir
  return (dir ++ "\\" ++ name)
